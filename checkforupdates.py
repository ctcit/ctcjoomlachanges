"""Python script to traverse the subtree of files rooted at '.'
   comparing all files with those rooted at ../public_html/,
   Print a message for all modified or new files.
   Run with 'python3 checkforupdates.py'
   Richard Lobb, last modified 3 June 2018.
"""
import os, filecmp

#TARGET = '/var/www/html/ctc/'
TARGET = '../public_html/'

def check(path):
    rel_path = path[2:]
    target = TARGET + rel_path
    if not os.path.exists(target):
        print("File {} is new".format(path))
    else:
        if not filecmp.cmp(path, target):
            print('File {} has been changed'.format(path))

def process_subtree(root):
   #print("Processing root", root)
   files = os.listdir(root)
   for file in files:
       if os.path.isdir(root + '/' + file):
           process_subtree(root + '/' + file)
       else:
           check(root + '/' + file)

def main():
    process_subtree('.')

main()

