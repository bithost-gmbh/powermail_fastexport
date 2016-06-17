.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================


.. _configuration-typoscript:

TypoScript Reference
--------------------


Properties
^^^^^^^^^^

.. container:: ts-properties

	=========================== ===================================== ====================
	Property                    Data type                             Default
	=========================== ===================================== ====================
	settings.maxExecutionTime_  int                                   1800
	settings.memoryLimit_       string                                2048MB
	=========================== ===================================== ====================


Property details
^^^^^^^^^^^^^^^^

.. only:: html

	.. contents::
		:local:
		:depth: 1


.. _ts-plugin-tx-extensionkey-settings-maxexecutiontime:

settings.maxExecutionTime
"""""""""""""""""""""""""

:typoscript:`plugin.tx_powermailfastexport.settings.maxExecutionTime = 1800`

Sets the PHP-setting `max_execution_time` using :func:`ini_set()`


.. _ts-plugin-tx-extensionkey-wrapitemandsub:

settings.memoryLimit
""""""""""""""""""""

:typoscript:`plugin.tx_powermailfastexport.settings.memoryLimit = 2048MB`

Sets the PHP-setting `memoryLimit` using :func:`ini_set()`


..

    .. _configuration-faq:

    FAQ
    ---

    Possible subsection: FAQ
