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
            if sys.argv[2]=='Retrait':
                test=porky.post(dict(amount=-int(sys.argv[3])),)
            elif sys.argv[2]=='Depot':
                test=porky.post(dict(amount=int(sys.argv[3])),)
            elif sys.argv[2]=='Nez':
                test=porky.update(dict(nose_color=sys.argv[3]),)
            elif sys.argv[2]=='Objectif':
                test=porky.update(dict(savings_goal=sys.argv[3]),)
            print test