# Headless Controller

## Headless system in general

A headless system is divided into two kinds of application. The api server, which contains all serverside logic but don't have any UI output,
and an UI application, which communicate with the server api and provide a UI, where user can interact with.
The UI could be any platform like a web or a native app.

### Pros

* You can use different technologies like programming language and frameworks on the api server and frontend.
* You can deliver different UI platforms with one api server.
* Frontend and backend devs can work independently. Their commitment rely only on the api.

### Cons

* Your application is getting more complex, because you have to manage more than one application and therefor often more technologies.
* More development time because you of more abstraction layer.

### Data Flow

Just take a look on the pro argument where frontend and backend dev can work independently. Why is this possible on a classic headless system?
A frontend dev needs a server side application, which is often a node app to keep their technology stack more simple.
With his application he can mock the api and provide test data to check if the frontend code is working.
Later on he can exchange the mock with the api, which contains the real application data.

![headless-system](/images/headless-system.png)

## Using controller instead of api

The approach of headless controller is to move the api commitment down to the render function, where application data
is passed to the template system. This gave us the ability to create a mocking controller to deliver the template with test data
without the need of two different applications.

![headless-system](/images/headless-controller.png)

### Pros

* Less complexity due to one application.
* Keeping the ability to write frontend and backend code more independently.
* Less development comparing to pure headless system because of no api abstraction layer.
* Good testing layer for template code.

### Cons

* No api to deliver different platforms.
* More knowledge of frontend devs, because they must know basic backend technologies.
* Comparing to more classic monolith the template must be able to run also on test data.

## Why we use headless controller

The two ends of a monolith and a headless system is flexibility vs. complexity. With the headless controller we want to
find a sweat spot to reduce the complexity but helping pure frontend devs to not rely on application data or logic.
They can create own controller with different sets of data to fulfil every edge case. While backend devs can just
add their logic and simple pass the data to the right template. The frontend controller should keep in the project to be able to
let frontend dev continue their work on lately changes.