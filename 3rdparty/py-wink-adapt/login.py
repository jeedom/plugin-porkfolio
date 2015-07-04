#!/usr/bin/python
import sys
import os
print '######################################'
print '##  Tentative d\'authentification    ##'
print '## Et ecriture du fichier de config ##'
print '######################################'
os.chdir(os.path.dirname(os.path.realpath(__file__)))
client_id = sys.argv[1]
client_secret = sys.argv[2]
username = sys.argv[3]
password = sys.argv[4]
if __name__ == "__main__":
    from wink import login
    login(base_url="https://winkapi.quirky.com", config_file="config.cfg",client_id=sys.argv[1],client_secret=sys.argv[2],username=sys.argv[3],password=sys.argv[4])
