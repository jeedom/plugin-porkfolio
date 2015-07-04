#!/usr/bin/python
#by sarakha63
import sys
import os
os.chdir(os.path.dirname(os.path.realpath(__file__)))
porkfolio_list=[]
index=int(sys.argv[1])-1
if __name__ == "__main__":
    import wink

    w = wink.init()

    for device in w.device_list():
        if device.device_type()=='piggy_bank':
            name='Inconnu'
            porkid='Inconnu'
            serial='Inconnu'
            mac='Inconnu'
            if device.id:
                porkid=device.id
            if device.data.get("name"):
                name=device.data.get("name")
            if device.data.get("serial"):
                serial=device.data.get("serial")
            if device.data.get("mac_address"):
                mac=device.data.get("mac_address")
            porkfolio_list.append([porkid,name,serial,mac])
print '{"porkid":"'+porkfolio_list[index][0]+'","porkname":"'+porkfolio_list[index][1]+'","porkserial":"'+porkfolio_list[index][2]+'","porkmac":"'+porkfolio_list[index][3]+'"}'
