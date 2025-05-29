<?php

return [

  'approve' => [
    'single' => [
      'label' => 'Approve',
      'modal' => [
        'heading' => 'Are you sure you want to approve ":label"?',
        'actions' => [
          'approve' => [
            'label' => 'Approve',
          ],
        ],
      ],
      'notifications' => [
        'approved' => [
          'title' => 'Successfully approved!',
        ],
      ],
    ],
    'multiple' => [
      'label' => 'Bulk Approve',
      'modal' => [
        'heading' => 'Are you sure you want to approve all selected ":label"?',
        'actions' => [
          'approve' => [
            'label' => 'Approve',
          ],
        ],
      ],
      'notifications' => [
        'approved' => [
          'title' => 'All items approved successfully!',
        ],
      ],
    ],
  ],
  'reject' => [
    'single' => [
      'label' => 'Reject',
      'modal' => [
        'heading' => 'Are you sure you want to reject ":label"?',
        'actions' => [
          'reject' => [
            'label' => 'Reject',
          ],
        ],
      ],
      'notifications' => [
        'rejected' => [
          'title' => 'Successfully rejected!',
        ],
      ],
    ],
    'multiple' => [
      'label' => 'Bulk Reject',
      'modal' => [
        'heading' => 'Are you sure you want to reject all selected ":label"?',
        'actions' => [
          'reject' => [
            'label' => 'Reject',
          ],
        ],
      ],
      'notifications' => [
        'rejected' => [
          'title' => 'All items rejected successfully!',
        ],
      ],
    ],
  ],
  'pending' => [
    'single' => [
      'label' => 'Mark as Pending',
      'modal' => [
        'heading' => 'Are you sure you want to mark ":label" as pending?',
        'actions' => [
          'pending' => [
            'label' => 'Mark as Pending',
          ],
        ],
      ],
      'notifications' => [
        'pended' => [
          'title' => 'Successfully marked as pending!',
        ],
      ],
    ],
  ],
];
