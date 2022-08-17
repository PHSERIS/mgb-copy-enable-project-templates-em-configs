## MGB Copy/Enable Project Template's EM Configs
********************************************************************************
Create projects, from project templates, that include the project template's EM configs.

### Getting started
********************************************************************************
Enable module on all projects by default. Then identify a project template that includes external modules with/without configuration settings.
Build a new project using the project template mentioned above and notice that the new project contains the same modules, and modules settings, as the project template.

### Excepted Use-Case
********************************************************************************

At MGB, we implemented part of our eConsent process using a project template. The project template contains the essential project structure to accommodate 80% of the MGB e-consent use-case.
Yet, a critical aspect of the project template is the External Modules needed for the MGB e-consent process. Currently, creating a project from a project template doesn't transfer the External Module settings from the project template into the newly created project.
It is this the expected use-case for this module; in order to facilitate project creation for predefined and structured processes that leverage on REDCap, the module "MGB Copy/Enable Project Template's EM Configs" can streamline project creation minimizing user error at the time of configuring external modules.


Note that: The module doesn't require configuration at the project level. Its only function it to assign the modules enabled on the project template onto this newly created project. 
The auto-configuring of modules in a new projects takes place on the Project Setup page (landing page of creating a project from a project template) during the first 3 seconds of project creation and cannot be retriggered.

### License
********************************************************************************
See the [LICENSE](?prefix=self_service_ext_mod&page=LICENSE.md) file for details

#### Comments, questions, or concerns, please contact:
********************************************************************************
Ed Morales at EMORALES@BWH.HARVARD.EDU