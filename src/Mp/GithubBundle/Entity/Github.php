<?php

namespace Mp\GithubBundle\Entity;


use Github\Client;
use Mp\GithubBundle\MpGithubBundle;
use Symfony\Component\Config\Definition\Exception\Exception;

class Github
{
  private static $instance;
  private $client;
  private $githubToken;
  private $githubUser;
  private $defaultRepo;

  private function __construct()
  {
    global $kernel;
    try {
      $this->client = new Client();
      $container = $kernel->getContainer();
      $this->githubToken = $container->getParameter('github_token');
      $this->githubUser = $container->getParameter('github_user');
      $this->defaultRepo = 'repo';
      $this->client->authenticate($this->githubToken, null, Client::AUTH_URL_TOKEN);
    }catch(Exception $e) {
      throw(new Exception('Cannot connect to github'));
    }
  }

  public static function getInstance()
  {
    if(self::$instance === null) {
      self::$instance = new Github();
    }
    return self::$instance;
  }

  public function findRepo($name, $params)
  {
    $repo = $this->client->api('repo')->find($name, $params);

    return $repo;
  }

  public function getUsers()
  {
    return $this->client->api('user')->all();
  }

  public function getFile($file, $branch)
  {
    return $this->client->api('repo')->contents()->show($this->githubUser, $this->defaultRepo, $file, $branch);
  }

  public function addFile($fileName, $fileContent, $branch)
  {
    return $this->client->api('repo')->contents()->create($this->githubUser, $this->defaultRepo, $fileName, $fileContent, 'Create new file', $branch);
  }

  public function removeFile($filePath, $sha, $branch)
  {
    return $this->client->api('repo')->contents()->rm($this->githubUser, $this->defaultRepo, $filePath, 'Remove file', $sha, $branch);
  }

  public function updateFile($filePath, $content, $message, $sha, $branch)
  {
    return $this->client->api('repo')->contents()->update($this->githubUser, $this->defaultRepo, $filePath, $content, $message, $sha, $branch);
  }

  public function createBranch($branchName, $sha)
  {
    $params = array(
      'ref' => 'refs/heads/' . $branchName,
      'sha' => $sha,
    );
    $newBranch = $this->client->api('git')->references()->create($this->githubUser, $this->defaultRepo, $params);

    return $newBranch;
  }

  public function getBranches()
  {
    $allBranches = $this->client->api('repo')->branches($this->githubUser, $this->defaultRepo);

    return $allBranches;
  }

  public function createPullRequest($title, $body, $head, $base)
  {
    $params = array(
      "title" => $title,
      "body" => $body,
      "head" => $head,
      "base" => $base,
    );
    $pullRequest = $this->client->api('pull_request')->create($this->githubUser, $this->defaultRepo, $params);

    return $pullRequest;
  }

  public function filesTree($sha)
  {
    try {
      $files = $this->client->api('git')->trees()->show($this->githubUser, $this->defaultRepo, $sha);
    } catch(Exception $e) {
      $files = array();
    }

    return $files;
  }

  private function addRepo($name, $description, $website)
  {
    $repo = $this->client->api('repo')->create($name, $description, $website, true);

    return $repo;
  }

}