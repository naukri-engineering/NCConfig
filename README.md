NCConfig
--------
NCConfig is a tool to monitor all crons which includes their status - Success/Failure, executing time, output, etc

What is Cron ?
Cron is a time-based job scheduler in Unix-like computer operating systems. People who set up and maintain software environments use cron to schedule jobs (commands or shell scripts) to run periodically at fixed times, dates, or intervals.


Features
--------
* Centralized system to manage all your crons running on multiple servers.
* Execution Statistics - You can get the time taken for each and every cron using this tool (Start Time, End Time)
* Graphical Statistics - You can check the execution statistics in graphical format
* Role based Management - Admin / Application Manager / Read Only
  - Admin : Will have all the rights like adding server, application, crons. etc
  - Application Manager - Will have the rights to manage the crons of the application which they own.
  - Ready Only - Will have only read only access
* Cron Success/Failure logs - Stores all the output of the crons in the databases which can be accessed anytime and also the cron status
* Single Change Settings - includes any actions that can change cron job settings excepting "Enable Logs", "Disable Logs", "Enable Cron Job" and "Disable Cron Job".
* Easy Integration - One-time setup/integration with your existing environment.
* NCConfig is totally free for non-commercial use.
* You can modify the source code to suit your system and use it internally, as well as develop customized tools aggregating features to NCConfig.
* All settings and the values collected are stored in a simple format and are fully open


Contributors
------------
* Amardeep Vishwakarma
* Rajeev Rai - mycronic.php
* Mustafa Gunderwala - QA


Contact Us
----------
engineering[at]naukri.com


LICENSE
-------
Copyright(c) 2014 Naukri.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
