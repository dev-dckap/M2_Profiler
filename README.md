# DCKAP_Profiler


## Overview
DCKAP Profiler is a must-have extension for the customers/developers who are looking for avenues to make their site faster. Faster websites also have a better ranking with Google. The DCKAP Profiler helps with displaying the profiler information at every page level and hence it helps to fine-tune the performance at the page level. There are also options to highlight the items which are over a specified time which will help to concentrate on the priority options first.

## Features
- Profiler data to be displayed for all the front end webpages.
- Option to Enable/Disable Profiler module from the backend.
- Disable optional columns namely Realmem and E-mallloc for Code
  profiler.
- Option to download as csv file for Code and Mysql profiler.

## Version​ ​&​ ​Compatibility​ ​Support
**Version:**  1.0.0 Stable

**Compatibility:** This extension is compatible from Magento Community 2.x and Magento
Enterprise 2.x to the latest versions.

## How​ ​to​ ​Install​ ​This​ ​Module?
**Step​ ​1:​** ​ ​Download​ ​the​ ​extension​ ​from​ ​My​ ​Downloadable​ ​Products​ ​in​ ​your account​ ​from​ ​our​ ​store​ ​or​ ​from​ ​Magento​ ​Marketplace.

**Step​ ​2:​** ​ ​Create​ ​a​ ​directory​ ​​`app\code\DCKAP\Profiler` ​in​ ​your Magento​ ​root​ ​directory​ ​and​ ​unzip​ ​here.

**Step​ ​3:​** ​​ Disable​ ​the​ ​cache​ ​to​ ​avoid​ ​flushing​ ​the​ ​cache,​ ​very​ ​often.​ ​It​ ​may affect​ ​performance​ ​for​ ​a​ ​while.​ ​However,​ ​you​ ​can​ ​skip​ ​this​ ​step.​ ​If​ ​you​ ​do so,​ ​clean​ ​the​ ​cache​ ​manually​ ​whenever​ ​needed.
`php​ ​bin/magento​ ​cache:disable `

**Step​ ​4:** ​ ​Enter​ ​the​ ​following​ ​at​ ​the​ ​command​ ​line​ ​to​ ​enable​ ​the​ ​module.
`php​ ​bin/magento​ ​module:enable​ DCKAP_Profiler`

**Step​ ​5:​** ​ ​Enter​ ​the​ ​following​ ​at​ ​the​ ​command​ ​line​ ​to​ ​run​ ​the​ ​setup​ ​scripts.
`php​ ​bin/magento​ ​setup:upgrade`

**Step​ ​6:​** ​ ​Enter​ ​the​ ​following​ ​at​ ​the​ ​command​ ​line​ ​if​ ​the​ ​mode​ ​is​ ​set​ ​to default​ ​or​ ​production​ ​to​ ​deploy​ ​all​ ​the​ ​static​ ​files.
`php​ ​bin/magento​ ​setup:static-content:deploy`

**Step 7:** ​​ Clear​ ​the​ ​cache​ ​to​ ​configure​ ​the​ ​settings​ ​in​ ​the backend​ ​(if​ ​you skipped​ ​Step3)
`php​ ​bin/magento​ ​cache:clean`

**Step 8:** Make sure that the below code is added in the **env.php** file
 
` 'profiler' => array ( 'class' => '\\Magento\\Framework\\DB\\Profiler','enabled' =>
 true,),`
 
 After implementing the above code, the file looks like
 
    array (
        'host' => 'localhost',
        'dbname' => 'xxxx',
        'username' => 'xxxx’',
        'password' => 'xxxx',
        'active' => '1',
        'profiler' => array (
            'class' => '\\Magento\\Framework\\DB\\Profiler',
            'enabled' => true,
        ),
    ),
 
That’s​ ​it.​ ​You​ ​are​ ​done.​ ​​If​ ​you​ ​still​ ​face​ ​any​ ​issues​ ​while​ ​installing,​ ​contact​ ​us​ ​at
 **extensions@dckap.com**