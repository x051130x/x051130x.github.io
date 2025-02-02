# uuz's Aerohakugyokurou

## Building

As of [protobuf/#11935](https://github.com/protocolbuffers/protobuf/issues/11935), you may need build google-protobuf with `-fno-lto`:

```
gem install google-protobuf --version <VERSION> --  --with-cflags="-fno-lto"
```

Which `<VERSION>` depends on `Gemfile.lock`.

And it is advised to use `bundler exec jekyll` instead of `jekyll`.