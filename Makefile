serve:
	php -S localhost:8200 -t public &

dev:
	npm run dev

start: serve dev