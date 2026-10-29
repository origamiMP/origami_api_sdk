# Tests — API repositories

An API repository test checks both sides of the call, which are both part of the public contract of the SDK: **the HTTP request sent** and **the DTO built** from the response. The client repository answering the call is built with the helpers of `client-repositories.md` (« Fake HTTP calls »).

## What to check, in this order

1. The request: `assertLastApiRequestWas('GET', 'sellers/42')` (path without `v1/`; `prefix: ''` for Oauth).
2. What it carries: `assertLastApiRequestQueryWas()` (values are always strings: `'page' => '2'`, `true` → `'1'`), `assertLastApiRequestJsonWas()`, or `assertLastApiRequestHadNoBody()`.
3. The DTO: `assertInstanceOf()`, then its id, then the values the case involves.

The expected JSON is written with snake_case keys and **literal values**, as sent to the API, not the SDK enums or dates (`OrigamiSellerDataApiRepositoryTest::test_create_case_1`). An unset param bag property is not sent: a case relying on it says so in its docblock.

## Expected coverage

- At least one test per public method.
- One more case per notable variation: filter not available (not sent), optional parameter not set, method called without its optional param bag, empty or particular response (`[]`, no `data` key), param bag defaults versus values set.
- One include test per method accepting includes (see below).
- Not retested in each API repository: what the client repository and `RestApiRepository` tests cover (HTTP errors, unreadable response).

### Include tests

A method accepts includes when its param bag uses `HasIncludes` and the DTO of its `getRequestMainDto()` declares at least one include that is not commented. The include rules themselves are in `references/code/param-bags.md`: if the tested method does not follow them, tell the user rather than writing a test that freezes the gap.

The include test (`OrigamiUserGroupInvitationDataApiRepositoryTest::test_get_history_case_2`):
1. requests the available includes plus an unknown or commented one with `setIncludes()`;
2. mocks a response holding the include, wrapped as Fractal does (`['data' => …]`);
3. checks that the `include` query only holds the available includes;
4. checks that the include is read: type of the included DTO and its id.

The filtering itself is tested once in `Traits/ParamBags/HasIncludesTest`. When an include is added or uncommented in a DTO, add the include test of every method returning this DTO.

## Reference tests per endpoint type

| Endpoint | Test |
|---|---|
| Paginated list with filters, pagination, search | `OrigamiSellerDataApiRepositoryTest::test_list_case_1` |
| Non-paginated list | `OrigamiModuleDataApiRepositoryTest::test_list_case_1` |
| Method called without its optional param bag | `OrigamiSellerDataApiRepositoryTest::test_list_case_4` |
| Reading an item from the `data` key | `OrigamiSellerDataApiRepositoryTest::test_get_case_1` |
| Creation (JSON body and response) | `OrigamiTaxDataApiRepositoryTest::test_create_case_1` |
| Partial update (204) | `OrigamiTaxDataApiRepositoryTest::test_update_case_1` |
| Action without body (204) | `OrigamiTaxDataApiRepositoryTest::test_delete_case_1` |
