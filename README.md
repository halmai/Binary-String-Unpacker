# Binary-String-Unpacker
A simple tool to convert a gzipped json string into human readable JSON.

This tool provides a form where you can paste a hexadecimal string like "01ab23e4fce2". After submitting the form, the following steps are performed:
- the hexadecimal string is converted into a binary representation
- decompressed by gzuncompress()
- JSON-decoded
- display the result of the individual steps or just the JSON version if it was requested.

If you applied the JSON Format Only button then the JSON is replied with the appropriate HTTP header.

Hint: with an appropriate browser extension, like [JSONView](https://addons.mozilla.org/en-US/firefox/addon/jsonview/) for Firefox, you can open/close the levels of the JSON structure.