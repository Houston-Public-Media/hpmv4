#!/usr/bin/env bash
cp /var/app/.env /var/app/staging/.env
chown webapp:webapp /var/app/staging/.env