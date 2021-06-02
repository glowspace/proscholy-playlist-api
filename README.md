# Portál ProScholy.cz API

Backend API server pro uživatelský portál ekosystému ProScholy.cz, který bude dostupný na
adrese https://portal.proscholy.cz.

## Funkce

Webová aplikace je napsaná v Laravelu a slouží jako micro service API server pro účely uživatelského portálu
ProScholy.cz, který bude dostupný na adrese portal.proscholy.cz.

Server vrací tyto data:

- mé skupiny (scholy, kapely),
- mé playlisty z databáze ProScholy.cz,
- sdílené playlisty mých skupin.

Tohoto API serveru se bude doptávat Nuxt.js FE aplikace (proscholy-portal-web) a případně mobilní aplikace Zpěvníku
ProScholy.cz (zpevnik-flutter).

## Aktuální stav

Aktuálně vyvíjíme prvotní alfa-verzi. Frontend server portálu se prototypuje v
repu https://github.com/proscholy/proscholy-portal-web.

## O projektu

Tato aplikace je součástí projektu ProScholy.cz, který má být zázemím pro lidi, kteří se chtějí modlit hudbou. Veškerý
vývoj probíhá díky dobrovolnictví všech, kteří se projektu věnují.

Pokud chcete pomoct tvorbě aplikací pro české křesťanské hudebníky, přidejte se k nám prostřednictvím
webu https://proscholy.cz
nebo rovnou pošlete pull request.

### Součást komunity Glow Space

Jsme součástí komunity digitálních křesťanů https://glowspace.cz.
