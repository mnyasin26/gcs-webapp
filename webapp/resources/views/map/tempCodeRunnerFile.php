<?php
date('H:i:s', end($telemetriLogs)->tPayload - $telemetriLogs[0]->tPayload).' s'