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
    'pending' => [
      'multiple' => [
        'label' => 'Bulk Set as Pending',
        'modal' => [
          'heading' => 'Are you sure you want to set all selected ":label" as pending?',
          'actions' => [
            'pending' => [
              'label' => 'Set as Pending',
            ],
          ],
        ],
        'notifications' => [
          'pending' => [
            'title' => 'All items marked as pending successfully!',
          ],
        ],
      ],
    ],
  ],
  'learn' => [
    'single' => [
      'label' => 'Learn',
      'modal' => [
        //
      ],
      'notifications' => [
        //
      ],
    ],
  ],
  'enroll' => [
    'single' => [
      'label' => 'Enroll',
      'modal' => [
        'heading' => 'Enroll in :label',
        'actions' => [
          'enroll' => [
            'label' => 'Enroll',
          ],
        ],
      ],
      'notifications' => [
        'enrolled' => [
          'title' => 'Successfully enrolled',
        ],
      ],
    ],
    'unenroll' => [
      'label' => 'Unenroll',
      'modal' => [
        'heading' => 'Unenroll from :label',
        'actions' => [
          'unenroll' => [
            'label' => 'Unenroll',
          ],
        ],
      ],
      'notifications' => [
        'unenrolled' => [
          'title' => 'Successfully unenrolled',
        ],
      ],
    ],
  ],
];
