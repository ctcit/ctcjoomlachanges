import os, filecmp

TARGET = '/var/www/html/ctc/'

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
