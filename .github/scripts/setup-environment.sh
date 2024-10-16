#!/bin/bash

set -eu

cp -r .github/files/bin bin
chmod 755 bin/console

cp .env.test .env
