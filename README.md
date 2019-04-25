# glagoljica

Ovaj direktorij daje jednostavne *PHP* skripte za transkripciju s latinice (zbog *siromaštva* glasovne reprezentacije u latinici, neki simboli dani su zapravo ćiriličnim znakovima ili znakovima iz grčkog alfabeta, no u pravilu se zaista radi o latinici) na glagoljicu i obratno.

## Sadržaj repozitorija

Iz sadržaja repozitorija izuzeta je ova *pročitajme* (*readme*) datoteka.

1.  [**output_json.php**](output_json.php) &ndash; funkcija `output_json` za jednostavni ispis u *JSON* formatu i po želji prekid izvršavanja *PHP* skripte,
2.  [**lookup_tables.php**](lookup_tables.php) &ndash; *lookup tablice* za transkripciju, to jest, asocijativni nizovi koji omogućuju funkcijsko preslikavanje iz skupova znakova,
3.  [**transcriptors.php**](transcriptors.php) &ndash; funkcije `lat2gla` i `gla2lat` za transkripciju s latinice na glagoljicu i s glagoljice na latinicu respektivno,
4.  [**get_transcription.php**](get_transcription.php) &ndash; demonstracija korištenja funkcija iz [*transcriptors.php*](transcriptors.php) pomoću metode *GET* s *JSON* izlazom.

## Korištenje [*get_transcription.php*](get_transcription.php)

Metodom *GET* (i samo metodom *GET*) moraju biti zadani parametri *text* i *dir* (i samo ta dva parametra). Parametar *text* zadaje, očito, tekst koji se želi transkribirati, a parametar *dir* mora imati jednu od sljedećih vrijednosti:

*   *l2g* &ndash; tekst (vrijednost parametra *text*) se transkribira s latinice na glagoljicu,
*   *g2l* &ndash; tekst (vrijednost parametra *text*) se transkribira s glagoljice na latinicu.

## TO DO

1.  Dodati komentare i *inline*-dokumentaciju u skripte za objašnjavanje korištenja skripti, a posebno objasniti kako, u transkripciji *l2g*, zadati glagoljički znak imenom umjesto jedinstvenim znakom i kako zahtijevati alternativnu varijantu glagoljičkog znaka.
2.  Stvari posebno naglašene u točki 1. objasniti i ovdje.
