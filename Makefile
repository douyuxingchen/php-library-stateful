.PHONY:test
test: ## 单元测试
	./vendor/bin/phpunit ./tests

.PHONY:test-func
test-func: ## 单元测试-方法测试
	sh ./tests/phpunit_func.sh

.PHONY:test-unit
test-unit: ## 单元测试-unit
	./vendor/bin/phpunit ./tests/Unit

.PHONY:test-feature
test-feature: ## 单元测试-feature
	./vendor/bin/phpunit ./tests/Feature

.PHONY:help
.DEFAULT_GOAL:=help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


