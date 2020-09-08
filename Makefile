DOCKER_COMPOSE  = docker-compose


##
## Docker
## -------
##
start: ## Start the project
start:
	$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate

stop: ## Stop the project
stop:
	$(DOCKER_COMPOSE) stop

clean: ## Stop the project and remove generated files
clean:
	$(EXEC_PHP) rm -rf vendor var/cache/* var/log/*

kill: ## Kill docker and clean project
kill: clean
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans