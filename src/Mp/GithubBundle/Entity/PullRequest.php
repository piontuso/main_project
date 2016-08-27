<?php

namespace Mp\GithubBundle\Entity;


class PullRequest
{
  protected $branches;
  protected $title;
  protected $body;
  protected $head;
  protected $base;


  /**
   * @return mixed
   */
  public function getHead()
  {
    return $this->head;
  }

  /**
   * @param mixed $head
   */
  public function setHead($head)
  {
    $this->head = $head;
  }

  /**
   * @return mixed
   */
  public function getBase()
  {
    return $this->base;
  }

  /**
   * @param mixed $base
   */
  public function setBase($base)
  {
    $this->base = $base;
  }

  /**
   * @return mixed
   */
  public function getBranches()
  {
    return $this->branches;
  }

  /**
   * @param mixed $branches
   */
  public function setBranches($branches)
  {
    $this->branches = $branches;
  }

  /**
   * @return mixed
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param mixed $title
   */
  public function setTitle($title)
  {
    $this->title = $title;
  }

  /**
   * @return mixed
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * @param mixed $body
   */
  public function setBody($body)
  {
    $this->body = $body;
  }
}