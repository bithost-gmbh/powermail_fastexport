.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _introduction:

Introduction
============


.. _what-it-does:

What does it do?
----------------

This extension extends powermail for faster export to .xlsx / .csv files by
fetching the data directly as an array without the Extbase ORM and
rendering the output directly without Fluid.
This is useful if you have many records to be exported.
In our test case with > 10'000 records to be exported, the
required time was reduced from 45 minutes to 45 seconds.
The extension powermail_fastexport extends the standard powermail export.
You can simply install the extension and use the export functionality just like before.