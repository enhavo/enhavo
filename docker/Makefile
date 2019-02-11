SHELL := /bin/bash

clean:
	docker rmi enhavo/enhavo:latest

build:
	docker build --no-cache -t enhavo/enhavo:latest .

run:
	docker run -d -it --rm -p 80:80 --name enhavo enhavo/enhavo:latest

push:
	docker push enhavo/enhavo:master