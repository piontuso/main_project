<?php

namespace Mp\GithubBundle\Entity;

class File
{
  protected $fileName;
  protected $fileContent;
  protected $branchName;
  protected $branchOptions;

  public function getFileName()
  {
    return $this->fileName;
  }

  public function setFileName($fileName)
  {
    $this->fileName = $fileName;
  }

  public function getFileContent()
  {
    return $this->fileContent;
  }

  public function setFileContent($fileContent)
  {
    $this->fileContent = $fileContent;
  }
  public function getBranchName()
  {
    return $this->branchName;
  }

  public function setBranchName($branchName)
  {
    $this->branchName = $branchName;
  }

  public function setBranchOptions($branchOptions) {
    $this->branchOptions = $branchOptions;
  }

  public function getBranchOptions()
  {
    return $this->branchOptions;
  }
}