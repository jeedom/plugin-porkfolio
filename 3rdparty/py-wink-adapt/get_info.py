#!/usr/bin/python
#by sarakha63
import os
import sys
os.chdir(os.path.dirname(os.path.realpath(__file__)))
if __name__ == "__main__":
    import time
    try:
        import wink
    except ImportError as e:
        import sys
        sys.path.insert(0, "..")
        import wink

    w = wink.init("config.cfg")
    c = w.piggy_banks()
    for porky in c:
        if porky.data.get("piggy_bank_id")==sys.argv[1]:
            if str(porky.data.get("balance"))=='None':
                balance='0'
            else:
                balance=str(porky.data.get("balance")/float(100))
            if str(porky.data.get("last_deposit_amount"))=='None':
                dernier='0'
            else:
                dernier=str(porky.data.get("last_deposit_amount")/float(100))
            nez=porky.data.get("nose_color")
            objectif=str(porky.data.get("savings_goal")/float(100))
            if str(porky.data.get("last_reading")["amount_updated_at"])=='None':
                datedepot='1'
            else:
                datedepot= str(porky.data.get("last_reading")["amount_updated_at"])
            if str(porky.data.get("last_reading")["orientation_updated_at"])=='None':
                dateretournement='0'
            else:
                dateretournement= str(porky.data.get("last_reading")["orientation_updated_at"])
            if str(porky.data.get("last_reading")["vibration_updated_at"])=='None':
                datemouvement='1'
            else:
                datemouvement= str(porky.data.get("last_reading")["vibration_updated_at"])
            print '{"Somme":'+balance+',"Dernier":'+dernier+',"Nez":"'+nez+'","Date depot":'+datedepot+',"Date Mouvement":'+datemouvement+',"Date Retournement":'+dateretournement+',"Objectif":'+objectif+'}'
