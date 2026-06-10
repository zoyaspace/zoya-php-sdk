# OpenAPI Contract

This directory holds the source API contract for the SDK.

## Direction

- the OpenAPI file is the source of truth for request and response contracts
- the SDK should stay ergonomic and hand-curated on top of the spec
- the spec should be usable for mock servers and partner-facing documentation

## Suggested Workflow

1. Add or refine an endpoint in `openapi.yaml`.
2. Align request and response examples with the backend contract.
3. Use a mock server against the spec while the backend implementation is still evolving.
4. Add or update the matching SDK resource class and tests.

## Mock Server

One practical option later is [Prism](https://github.com/stoplightio/prism), which can serve mock responses directly from the OpenAPI document.

Example:

```bash
docker run --rm -p 4010:4010 \
  -v "$PWD/openapi:/tmp/openapi" \
  stoplight/prism:5 \
  mock -h 0.0.0.0 /tmp/openapi/openapi.yaml
```
