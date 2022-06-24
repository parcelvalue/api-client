#!/bin/bash

cp bin/git_hooks/composer_all .git/hooks/pre-push
chmod +x .git/hooks/pre-push
