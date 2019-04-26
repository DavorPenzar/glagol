# glagol

Ovaj direktorij daje jednostavne *PHP* skripte za transkripciju s latinice (zbog *siromaštva* glasovne reprezentacije u latinici, neki simboli dani su zapravo ćiriličnim znakovima ili znakovima iz grčkog alfabeta, no u pravilu se zaista radi o latinici) na glagoljicu i obratno.

## Sadržaj repozitorija

Iz sadržaja repozitorija izuzeta je ova *pročitajme* (*readme*) datoteka.

1.  [**output_json.php**](output_json.php) &ndash; funkcija `output_json` za jednostavni ispis u *JSON* formatu i po želji prekid izvršavanja *PHP* skripte,
2.  [**lookup_tables.php**](lookup_tables.php) &ndash; *lookup tablice* za transkripciju, to jest, asocijativni nizovi koji omogućuju funkcijsko preslikavanje iz skupova znakova,
3.  [**transcriptors.php**](transcriptors.php) &ndash; funkcije `lat2gla` i `gla2lat` za transkripciju s latinice na glagoljicu i s glagoljice na latinicu respektivno,
4.  [**index.php**](index.php) &ndash; demonstracija korištenja funkcija iz [*transcriptors.php*](transcriptors.php) pomoću metode *GET* s *JSON* izlazom.

## Korištenje [*index.php*](index.php)

Metodom *GET* (i samo metodom *GET*) moraju biti zadani parametri *text* i *dir* (i samo ta dva parametra). Parametar *text* zadaje, očito, tekst koji se želi transkribirati, a parametar *dir* mora imati jednu od sljedećih vrijednosti (kao *string*):

*   *l2g* &ndash; tekst (vrijednost parametra *text*) se transkribira s latinice na glagoljicu,
*   *g2l* &ndash; tekst (vrijednost parametra *text*) se transkribira s glagoljice na latinicu.

Ulazni tekst može sadržavati, među ostalima, razmake, znakove koji ne pripadaju *ASCII*-u (na primjer, ako je ulazni tekst na glagoljici i traži se transkripcija na latinicu) i slične *smetnje* da se nalazi u valjanom *URL*-u, stoga je dopušteno da parametar *text* bude zadan i u *JSON* formatu. Naravno, u slučaju jednostavnih tekstova, *JSON* formatiranje nije potrebno pa se može zadati i običnim tekstom. Dakle, valjani su svi sljedeći pozivi (`localhost/.../` je samo primjer mogućeg formata početka adrese &mdash; valjanost poziva ne referira se na taj dio, nego na dio od `/glagol` nadalje):

```
localhost/.../glagol?text=Lorem%20ipsum&dir=l2g
localhost/.../glagol?text="Lorem%20ipsum"&dir=l2g
localhost/.../glagol?text="Pjesnici%20su%20\u010du\u0111enje%20u%20svijetu"&dir=l2g
```

S druge strane, zbog jednostavnosti vrijednosti koje parametar *dir* smije poprimiti, njegovo zadavanje nije dopušteno *JSON* enkapsulacijom. Drugim riječima, sljedeći pozivi nisu valjani (primijeti `dir="l2g"` umjesto `dir=l2g` kao u primjerima gore):

```
localhost/.../glagol?text=Lorem%20ipsum&dir="l2g"
localhost/.../glagol?text="Lorem%20ipsum"&dir="l2g"
localhost/.../glagol?text="Pjesnici%20su%20\u010du\u0111enje%20u%20svijetu"&dir="l2g"
```

## TO DO

1.  Dodati ovdje tablicu glagoljice, kako se slova transkribiraju i kako se slova zovu.
2.  Objasniti ovdje kako, u transkripciji *l2g*, zadati glagoljički znak imenom umjesto jedinstvenim znakom i kako zahtijevati alternativnu varijantu nekog glagoljičkog znaka.
