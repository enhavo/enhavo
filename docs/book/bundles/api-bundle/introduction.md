## Introduction

The Api bundle is a fundamental bundle for the entire enhavo platform. It is the starting point for every request. 
In contrast to other common approaches like API-Platform, we wanted to define the routes and their corresponding
entry points ourselves from the start. This gives us more control over the available routes. 
This bundle introduces endpoints, a replacement for controllers, that follow common patterns used throughout enhavo,
making them more reusable and extendable. It also provides a description API that exposes an OpenAPI-compliant
specification of your endpoints.