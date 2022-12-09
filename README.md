# mall-of-turkey
Development GuideLine
---------------------

These guidelines are currently a work in progress. However, it broadly outlines how I want the code to be structured.

In addition to these following guidelines, I also expect developers to create a few documents that broadly describe technical details of the system. Those will include an ERD , a technical document to explain various components of code, deployment process , setup of dev environment etc and overall architecture of the system.


I have supplied you a git repo. 
* Each developer creates a fork. 
* Use a PR mechanism. That allows me to view the code before it is merged to main repo. 
* Process of using forks is not very different from using a main repo.
* You will click on the Fork icon in github. Github will create a copy of that repo in your account. You will clone that repo and call it `origin`.
* You will continue to commit and push to that repo.
* Anytime your code is good enough so that others should see it, or should be tested by our QA, you will create a Pull Request to the main repo.
* The main repo is generally called `upstream`. 
* When the `upstream` is updated, you will need to pull the upstream and merge it to your code.

MUST
----
* .env files have been supplied. Setup your db configuration [name , user, pw, host] to match the .env.example. If you use docker, it will do it for you. With that standardized, when new dev are added, it easier to follow the process.
* [Create Service classes](https://github.com/SquareHouse-me/joko-backend/pull/18) . Actual work should happen in service classes. Write unit tests to confirm these Service classes work. Write tests to confirm API work.
* [Avoid magic numbers](https://www.pluralsight.com/tech-blog/avoiding-magic-numbers/). Use class constants. or Value Objects.
* Use [SOLID principle](https://en.wikipedia.org/wiki/SOLID). I don't expect anyone to be a master at this, nor do I want to. All I expect that you have separate business logic into relevant service classes. Business knowledge should stay within relevant classes. The last thing I want to see is 50 lines of code crammed into a controller. A few basic lines of code is Ok in Controller , where no special cases are being handled. Like load a simple record and pass to a view. [Click here](https://github.com/SquareHouse-me/joko-backend/pull/18) to see an example of what I mean.
* Try to convert the request object in the controller to object. Try to pass on objects as much as you can, as opposed to passing raw data. Type hinting ensures we are passing the right object.
* Views should not have knowledge of Business. View should not access DB or Auth or Session. Supply relevant data to view through variables from Controllers.
* Code should be structured into small functions. Avoid use of `else` when you can . I understand it's not always avoidable. Especially in blade templates you can't avoid `else`, but I see a lot of places where use of else creates spaghetti code. that can be improved.
* Any call to CURL or any third party service should be extracted to a queue or offline process. Call to a browser should rarely make a 3rd party service call.
* Never upload vendor folder to the server, or to version control. Never Make changes to the vendor folder, unless for debugging purposes that will not go to the server.
* Use Logging. I use monolog/monolog to document the flow of app.
* Uploaded Images and Image Assets should use separate CDN urls, picked form config.

* All DB changes should come through migrations.
* Use DB Seeds to populate the DB.
* Avoid DB raw queries directly. Use Laravel Eloquent model, its relations and query builder as much as possible. Use scopes to clarify / document some relationship or filters.
* Avoid JSON fields that may be searched.


API
----
* Follow REST API standards. Learn the [basics of idempotency](https://restfulapi.net/idempotent-rest-apis/), if you don't already  know it.
* Return proper HTTP status code. It is not acceptable to return 200 code with an API that is failing for whatever reason. [Read the book] (https://apisyouwonthate.com/) 
* Ensure all your APIs endpoints have a representation in json Postman collection.
* Postman has some rudimentary testing. Better to invest a bit of time in writing tests for API through js.
 
Admin UI
--------
* If UI design disagree with following recommendation, design will have a preference. 
* Try to have clear separate groups of input fields.
* Errors should be distinctly displayed.
* Color of messages should signify its importance.
* Error messages should never go away off-screen without user dismissing them.
* Drop down should be avoided when you have only a few choices [upto three].
* Form fields should have clickable labels, when possible. 
* Avoid un-necessary movements. If there is animation, it should not be too fast.

 
Nice To Have
------------
* Learn the latest coding standards , PSR-12 , or even PSR-2. It's not too hard to do.
* Use Docker. It allows setting up dev env seamlessly. I will provide something that repo to get that started quickly.
* Once code reaches a point that we have a base set up, build features using separate branches. Keep merging those branches to parent when completed. This ensures that no spill over of incomplete work reaches to the parent. Hence QA are not interrupted by incomplete features you haven't finished. 



Setup Guidelines
-----------------
GIT
----
1. For Windows users , [install Git Bash](https://git-scm.com/downloads)
2. Run the following commands in git-bash
3. Following example uses SSH based urls. You may use https based url. 

$ `pwd`
/d/Code

$ `git clone git@github.com:najamhaq/mall-of-turkey.git`
```
Cloning into 'mall-of-turkey'...
remote: Enumerating objects: 28, done.
remote: Counting objects: 100% (28/28), done.
remote: Compressing objects: 100% (18/18), done.
remote: Total 28 (delta 2), reused 28 (delta 2), pack-reused 0
Receiving objects: 100% (28/28), 48.00 KiB | 0 bytes/s, done.
Resolving deltas: 100% (2/2), done.
```

$ `cd mall-of-turkey`

$ `git remote -v`
```
origin  git@github.com:najamhaq/mall-of-turkey.git (fetch)
origin  git@github.com:najamhaq/mall-of-turkey.git (push)
```

$ `git remote add upstream git@github.com:SquareHouse-me/mall-of-turkey.git`

$ `git remote -v`
```
origin  git@github.com:najamhaq/mall-of-turkey.git (fetch)
origin  git@github.com:najamhaq/mall-of-turkey.git (push)
upstream        git@github.com:SquareHouse-me/mall-of-turkey.git (fetch)
upstream        git@github.com:SquareHouse-me/mall-of-turkey.git (push)
```

Once there is an update in upstream which you should incorporate into your code :
$ `git remote update`
```
Fetching origin
Fetching upstream
remote: Enumerating objects: 1, done.
remote: Counting objects: 100% (1/1), done.
remote: Total 1 (delta 0), reused 0 (delta 0), pack-reused 0
Unpacking objects: 100% (1/1), done.
From github.com:SquareHouse-me/mall-of-turkey
   d4d8f1e..c22287a  master     -> upstream/master
```
$ `git merge upstream/master`
```
Already up-to-date!
Merge made by the 'recursive' strategy.
```

Docker Setup
------------
If you have docker installed, just switch to a directory where docker-compose.yml file is stored and run the following command.

$ `docker-compose up -d`
This will setup three containers on your machine, `squarehouse-db`, `squarehouse`, `squarehouse-webserver` .
You can ssh into `squarehouse` , by using docker desktop UI and run command 
$ `/bin/bash`
$ `cd squarehouse`
$ `../bin/init.sh`


* Add following entry in your hosts file:

`127.0.0.1 mall-of-turkey`

The application will now be accessible at http://mall-of-turkey:9090/

Telescope is installed and is accessible at http://mall-of-turkey:9090/telescope