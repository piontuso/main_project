<?php

namespace Mp\GithubBundle\Controller;

use Mp\GithubBundle\Entity\Branch;
use Mp\GithubBundle\Entity\File;
use Mp\GithubBundle\Entity\Github;
use Mp\GithubBundle\Entity\PullRequest;
use Mp\GithubBundle\Form\GithubFileType;
use Mp\GithubBundle\Form\PullRequestType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/github", name="github")
     */
    public function indexAction()
    {

      $github = Github::getInstance();
      $c = $github->getBranches();
      $a = 0;
        return $this->render('MpGithubBundle:Default:index.html.twig');
    }

    /**
     * @Route("/github/add", name="github_add")
     */
    public function addAction(Request $request)
    {
        $github = Github::getInstance();
        $file = new File();
        $file->setFileName('newfile.txt');
        $branches = $github->getBranches();

        $options = array();
        foreach($branches as $branch) {
          $options[$branch['commit']['sha'] . '|' . $branch['name']] = $branch['name'];
        }
        $file->setBranchOptions($options);

        $form = $this->createForm(new GithubFileType(), $file);
        $form->handleRequest($request);

        if ($form->isValid()) {

          $branchName = explode("|" , $file->getBranchName());
          $status = $github->addFile($file->getFileName(), $file->getFileContent(), $branchName[1]);

          $request->getSession()
            ->getFlashBag()
            ->add('success', 'File has added with successfully to branch "'. $branchName[1] .'"!');

          return $this->redirectToRoute('github_add');
        }

        return $this->render('MpGithubBundle:Default:add.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/github/update", name="github_update")
     */
    public function updateAction(Request $request)
    {
        $github = Github::getInstance();
        $localFile = new File();
        $file = $request->query->get('file');
        $branch = $request->query->get('branch');
        $sha = $request->query->get('sha');
        $remoteFile = $github->getFile($file, $branch);
        $content = base64_decode($remoteFile['content']);
        $localFile->setFileName($file);
        $localFile->setFileContent($content);

        $form = $this->createForm(new GithubFileType(), $localFile);
        $form->handleRequest($request);
        $form->get('create')->setData('Update');

        if ($form->isValid()) {
          $github->updateFile($file, $localFile->getFileContent(), 'Update file.', $sha, $branch);
          $request->getSession()
            ->getFlashBag()
            ->add('success', 'File has updated with successfully to branch !');

          return $this->redirectToRoute('github_add');
        }

        return $this->render('MpGithubBundle:Default:add.html.twig', array(
          'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/github/remove", name="github_remove")
     */
    public function removeAction(Request $request)
    {
        if (isset($_GET['sha']) && isset($_GET['file']) && isset($_GET['branch'])) {
          $github = Github::getInstance();
          $status = $github->removeFile($_GET['file'], $_GET['sha'], $_GET['branch']);

          return $this->redirectToRoute('github_list');
        } else {
          $request->getSession()
            ->getFlashBag()
            ->add('warning', 'Missing parameters !');
        }

        return $this->render('MpGithubBundle:Default:index.html.twig');
    }

    /**
     * @Route("/github/merge", name="github_merge")
     */
    public function mergeAction(Request $request)
    {
      $github = Github::getInstance();
      $pullRequest = new PullRequest();
      $branches = $github->getBranches();

      $options = array();
      foreach($branches as $branch) {
        $options[$branch['commit']['sha'] . '|' . $branch['name']] = $branch['name'];
      }

      $pullRequest->setBranches($options);
      $form = $this->createForm(new PullRequestType(), $pullRequest);

      $form->handleRequest($request);

      if ($form->isValid()) {
        $head = $this->explodeBranchOption($pullRequest->getHead(), 1);
        $base = $this->explodeBranchOption($pullRequest->getBase(), 1);
        $title = $pullRequest->getTitle();
        $body = $pullRequest->getBody();
        $github->createPullRequest($title, $body, $head, $base);
        $request->getSession()
          ->getFlashBag()
          ->add('success', 'Pull request has created with successfully !');

        return $this->redirectToRoute('github_merge');
      }


      return $this->render('MpGithubBundle:Default:merge.html.twig', array(
        'form' => $form->createView(),
      ));
    }

    private function explodeBranchOption($branchOption, $index)
    {
      $branchName = explode("|" , $branchOption);

      return isset($branchName[$index]) ? $branchName[$index] : null;
    }

    /**
     * @Route("/github/list", name="github_list")
     */
    public function listAction(Request $request)
    {
        $github = Github::getInstance();
        $branches = $github->getBranches();

        $options = array();
        foreach($branches as $branch) {
            $options[$branch['commit']['sha'] . '|' . $branch['name']] = $branch['name'];
        }

        $branch = new File();
        $form = $this->createFormBuilder($branch)
          ->add('branchName', ChoiceType::class, array(
            'choices' => $options,
            'attr' => array('class' => 'form-control'),
          ))
          ->add('create', SubmitType::class, array(
            'label' => 'Show tree',
            'attr' => array('class' => 'btn btn-default'),
          ))
          ->getForm();

          $form->handleRequest($request);

          if ($form->isValid()) {
            $branchName = explode("|" , $branch->getBranchName());
            $tree = $github->filesTree($branchName[0]);

            return $this->render('MpGithubBundle:Default:list.html.twig', array(
              'form' => $form->createView(),
              'items' => $tree['tree'],
            ));
          }

      return $this->render('MpGithubBundle:Default:list.html.twig', array(
        'form' => $form->createView(),
      ));
    }
}
