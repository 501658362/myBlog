#!/bin/bash
Cur_Dir=$(pwd)
Cur_who=$(who)
echo $Cur_Dir
echo $Cur_who
cd /home/wwwroot/chenyanjin.top/myBlog/
git stash
git pull
git stash pop >/dev/null 2>&1