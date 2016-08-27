<?php

namespace Mp\GithubBundle\Entity;


class Branch
{
  protected $branchName;
  protected $branchSha;

  public function getBranchName()
  {
    return $this->branchName;
  }

  public function setBranchName($branchName)
  {
    $this->branchName = $branchName;
  }
}