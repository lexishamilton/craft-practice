<?php

namespace lexishamilton\craftclearjobs\variables;
use lexishamilton\craftclearjobs\Plugin;

class ClearJobsVariable
{
    public function getClearJobs(): array {
        return Plugin::getInstance()->clear->getJobs();
    }
}