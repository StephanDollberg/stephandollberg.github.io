#!/usr/bin/env python

#transforms jekyll dir structure to directory servable structure

import os
import sys
import shutil

for root, subdirs, files in os.walk('.'):
    if len(subdirs) == 0 and len(files) == 1 and files[0] == 'index.html':
        with open(root + '/' + files[0]) as f:
            data = f.read()

        shutil.rmtree(root)

        with open(root, 'w') as f:
            f.write(data)

        print('replaced', root)
