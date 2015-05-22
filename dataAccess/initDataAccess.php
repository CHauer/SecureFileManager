<?php
/**
 * Created by PhpStorm.
 * User: Christoph
 * Date: 14.05.2015
 * Time: 04:07
 */

//load model class
require("models/User.php");
require("models/AuthToken.php");
require("models/Comment.php");
require("models/Entry.php");
require("models/ForumThread.php");
require("models/LogEntry.php");
require("models/Role.php");
require("models/UserFile.php");

//load repositories
require("UserRepository.php");
require("RoleRepository.php");
require("FilesRepository.php");
require("ForumRepository.php");
require("LogEntryRepository.php");
require("AuthTokenRepository.php");
?>