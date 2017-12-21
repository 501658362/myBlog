#!/bin/bash
Cur_Dir=$(pwd)
echo $Cur_Dir
cd /home/wwwroot/chenyanjin.tk/myBlog/
git stash
git pull
git stash pop