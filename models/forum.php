<?php
/* 
 * Project: Nathan MVC
 * File: /models/home.php
 * Purpose: model for the home controller.
 * Author: Nathan Davison
 */

class ForumModel extends BaseModel
{
    public function __construct($db)
    {
        parent::__construct($db);
    }

    //data passed to the home index view
    public function index()
    {
        $this->viewModel->set("pageTitle","Forum");
        return $this->viewModel;
    }

    public function thread($threadId)
    {
        $this->viewModel->set("pageTitle", "View thread");

        $forumrepo = new ForumRepository($this->db);

        $thread = $forumrepo->GetForumThreadById($threadId);

        $this->viewModel->set("thread", $thread);
        $this->viewModel->set("entries", $forumrepo->GetEntriesForThread($thread->ForumThreadId));

        return $this->viewModel;
    }

    public function deleteEntry()
    {
        $this->viewModel->set("pageTitle", "Delete answer");
        return $this->viewModel;
    }

    public function newThread()
    {
        $this->viewModel->set("pageTitle", "Start a new thread");
        return $this->viewModel;
    }
}

?>
