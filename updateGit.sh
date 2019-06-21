#!/bin/bash
Cur_Dir=$(pwd)
echo $Cur_Dir
cd /home/wwwroot/chenyanjin.top/myBlog/
git stash
git pull
git stash pop >/dev/null 2>&1