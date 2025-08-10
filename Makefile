serve:
	php -S localhost:8200 -t public &

dev:
	npm run dev

start: serve dev

stop:
	pkill -f "php -S localhost:8200 -t public &"
	pkill -f "npm run dev"