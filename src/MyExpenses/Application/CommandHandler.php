<?php

namespace MyExpenses\Application;

interface CommandHandler
{
    public function handle(Command $command);
}
