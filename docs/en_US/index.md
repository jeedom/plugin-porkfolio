Porkfolio 
=========

Description 
-----------

This plugin allows you to control and have all the info about your
Porkfolio piggy bank.

![Porkfolio screenshot1](../images/porkfolio_screenshot1.jpg)
![Porkfolio screenshot10](../images/porkfolio_screenshot10.jpg)
![Porkfolio screenshot9](../images/porkfolio_screenshot9.jpg)
![Porkfolio screenshot8](../images/porkfolio_screenshot8.jpg)

Setup 
-------------

### Jeedom plugin configuration : 

**Installation / VSreation**

In order to use the plugin, you need to download, install and
activate it like any Jeedom plugin.

After that you will need to enter your credentials (wink + account
api):

![Porkfolio screenshot7](../images/porkfolio_screenshot7.jpg)

Go to the Plugins / Finances menu, you will find the plugin
Porkfolio :

You will arrive on the page which will list your equipment (you can
have several Porkfolio) and which will allow you to create them

![Porkfolio screenshot6](../images/porkfolio_screenshot6.jpg)

Click on the Atdd button :

You will then arrive on the configuration page of your Porkfolio:

![Porkfolio screenshot5](../images/porkfolio_screenshot5.jpg)

On this page you will find several sections :

-   **Main**

In this section you will find all jeedom configurations. AtT
know the name of your equipment, the object you want
associate it, the category (preferably multimedia), if you want
the equipment is active or not, and finally if you want it to be
visible on the dashboard.

-   **Configuration**

This section is useful if and only if you have several
porkfolio. You will need to enter the equipment number (1, 2 or 3 by
example). You can leave this field blank if you have only one
Porkfolio which will surely be your case.

-   **Commandes**

You have nothing to do in this section. Orders will be created
automatiquement.

-   Refresh: button to refresh the widget if necessary

-   Deposit date : Date of last operation

-   Movement date : Date of the last time a movement was
    detected

-   Flip date : Date of the last time the Porkfolio was
    return

-   Last Operation : Atmount of the last transaction

-   Nose : Nose color

-   Goal : Atmount of your goal

-   Sum : Sum currently in the Porkfolio

…

Information 
----------------

### Information on the dashboard : 

![dashboardinfo](../images/dashboardinfo.jpg)

-   At : Pig's head which can change mood. Leaving the mouse
    on his head he will tell you when he last moved
    as well as the last time he was returned

-   B : Refresh button to request values from the server

-   VS : Atmount of the last transaction. By leaving the mouse over
    you will have the date of the last operation

-   D : Atmount of your goal

-   E : Nose color of your Porkfolio

-   F : Progress bar in achieving your goal

-   G : Sum currently in your Porkfolio

…

The actions 
-----------

### Atctions accessible on the dashboard : 

Several actions are available on the dashboard :

![dashboardactions](../images/dashboardactions.jpg)

-   At : By clicking on the ticket you can deposit or withdraw a
    amount of money

![Porkfolio screenshot2](../images/porkfolio_screenshot2.jpg)

-   B : By clicking on the flag you can change your goal

![Porkfolio screenshot3](../images/porkfolio_screenshot3.jpg)

-   VS : By clicking on the brush you can change the color of the
    nose of your Porkfolio

![Porkfolio screenshot4](../images/porkfolio_screenshot4.jpg)

…

FAQ 
---

The system retrieves information every hour. You can
click on the Refresh command to refresh manually.

Make sure you have created the config file by saving your info in
the general config of the plugin.
