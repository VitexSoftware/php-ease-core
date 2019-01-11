#!/bin/bash
docker build -t vitexus/ease-core .
docker push vitexus/ease-core
cd debian
./deb-package.sh
