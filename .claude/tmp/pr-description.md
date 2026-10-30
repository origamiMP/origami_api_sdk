Supprime la validation des réponses de l'API dans les DTOs, proposée dans [SDK : supprimer la validation des réponses dans les DTO](https://app.notion.com/p/origami-marketplace/SDK-supprimer-la-validation-des-r-ponses-dans-les-DTO-408dbde12c8e81268ceee563997c67ac). L'API est la source de vérité, et la validation prenait 96 à 98 % du temps de construction d'un DTO : en staging, une liste de 100 vendeurs avec custom fields passe de 1 520 ms à 39 ms. Changement cassant (suppression de `Validator`, renommage de `getResponseContentDataOrEmptyObject()`).

- `ApiResponseDto.php` : remplit les propriétés sans valider ; une clé absente rend le DTO non constructible, sauf les includes et les clés de `getOptionalKeys()`.
- `UserDto.php`, `SellerDto.php`, `FormDto.php`, `FormFieldParamsDto.php`, `FormPrefillSourceDto.php`, `DocumentTypeDto.php`, `OrigamiApiErrorDto.php` : les 15 clés que l'API n'envoie pas toujours, vérifiées dans ses transformers.
- `RestApiRepository.php` : `getResponseContentData()` lève la nouvelle `OrigamiApiResponseWithoutDataException` quand `data` manque.
- `FeatureFlagListDto.php` : vérifie que l'état de chaque flag est un booléen.
- `composer.json` : retire `illuminate/validation`, et avec elle 10 autres paquets.
- `TestsExceptions.php` : `assertThatExceptionWasThrown()` vérifie qu'une partie du message est contenue, plus le message exact.
- `ApiResponseDtoTest.php` : clé obligatoire absente, include absent, clé optionnelle absente (`test_fill_case_*`).
- `dtos.md` : la section « Validation » du skill devient « Missing keys (`getOptionalKeys()`) ».
