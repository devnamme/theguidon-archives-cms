<?php

/**
 * EXPERIMENTAL REMOVE
 */
function archivescms_add_legacy() {
  // press = 5
  // freshmanual = 6
  // gradmag = 7
  // primer = 8
  // other = 10
  // $admin_id = 2;

  # volume num, issue num
  # slug
  # title, date, post_category
  $issues = array(
    // array(1, 1, 'vol-1-no-1', '', '1929-06-22T12:00:00+00:00'),
    // array(1, 2, 'vol-1-no-2', '', '1929-07-06T12:00:00+00:00'),
    // array(1, 3, 'vol-1-no-3', '', '1929-07-20T12:00:00+00:00'),
    // array(1, 4, 'vol-1-no-4', '', '1929-08-03T12:00:00+00:00'),
    // array(1, 5, 'vol-1-no-5', '', '1929-08-17T12:00:00+00:00'),
    // array(1, 6, 'vol-1-no-6', '', '1929-09-02T12:00:00+00:00'),
    // array(1, 7, 'vol-1-no-7', '', '1929-09-14T12:00:00+00:00'),
    // array(1, 8, 'vol-1-no-8', '', '1929-10-01T12:00:00+00:00'),
    // array(1, 9, 'vol-1-no-9', '', '1929-10-15T12:00:00+00:00'),
    // array(1, 10, 'vol-1-no-10', '', '1929-11-01T12:00:00+00:00'),
    // array(1, 11, 'vol-1-no-11', '', '1929-11-15T12:00:00+00:00'),
    // array(1, 12, 'vol-1-no-12', '', '1929-12-02T12:00:00+00:00'),
    // array(1, 13, 'vol-1-no-13', '', '1929-12-21T12:00:00+00:00'),
    // array(1, 14, 'vol-1-no-14', '', '1930-01-10T12:00:00+00:00'),
    // array(1, 15, 'vol-1-no-15', '', '1930-01-25T12:00:00+00:00'),
    // array(1, 16, 'vol-1-no-16', '', '1930-02-10T12:00:00+00:00'),
    // array(1, 17, 'vol-1-no-17', '', '1930-03-03T12:00:00+00:00'),
    // array(1, 18, 'vol-1-no-18', '', '1930-03-20T12:00:00+00:00'),
    // array(2, 1, '', '', '1930-06-25T12:00:00+00:00'),
    // array(2, 2, '', '', '1930-07-12T12:00:00+00:00'),
    // array(2, 3, '', '', '1930-07-26T12:00:00+00:00'),
    // array(2, 4, '', '', '1930-08-09T12:00:00+00:00'),
    // array(2, 5, '', '', '1930-08-23T12:00:00+00:00'),
    // array(2, 6, '', '', '1930-09-06T12:00:00+00:00'),
    // array(2, 7, '', '', '1930-09-20T12:00:00+00:00'),
    // array(2, 8, '', '', '1930-10-04T12:00:00+00:00'),
    // array(2, 9, '', '', '1930-10-18T12:00:00+00:00'),
    // array(2, 10, '', '', '1930-11-15T12:00:00+00:00'),
    // array(2, 11, '', '', '1930-11-29T12:00:00+00:00'),
    // array(2, 12, '', '', '1930-12-17T12:00:00+00:00'),
    // array(2, 13, '', '', '1931-01-17T12:00:00+00:00'),
    // array(2, 14, '', '', '1931-01-31T12:00:00+00:00'),
    // array(2, 15, '', '', '1931-02-14T12:00:00+00:00'),
    // array(2, 16, '', '', '1931-03-04T12:00:00+00:00'),
    // array(3, 1, '', '', '1931-06-18T12:00:00+00:00'),
    // array(3, 2, '', '', '1931-07-02T12:00:00+00:00'),
    // array(3, 3, '', '', '1931-07-18T12:00:00+00:00'),
    // array(3, 4, '', '', '1931-07-31T12:00:00+00:00'),
    // // array(3, 5, '', '', '1931-08-14T12:00:00+00:00'), // missing
    // array(3, 6, '', '', '1931-08-28T12:00:00+00:00'),
    // array(3, 7, '', '', '1931-09-07T12:00:00+00:00'),
    // array(3, 8, '', '', '1931-09-25T12:00:00+00:00'),
    // array(3, 9, '', '', '1931-10-17T12:00:00+00:00'),
    // array(3, 10, '', '', '1931-11-16T12:00:00+00:00'),
    // // array(3, 11, '', '', '1931-??-??T12:00:00+00:00'), // missing
    // array(3, 12, '', '', '1931-12-17T12:00:00+00:00'),
    // array(3, 13, '', '', '1932-01-23T12:00:00+00:00'),
    // array(3, 14, '', '', '1932-02-13T12:00:00+00:00'),
    // array(4, 1, '', '', '1932-07-07T12:00:00+00:00'),
    // array(4, 2, '', '', '1932-07-15T12:00:00+00:00'),
    // array(4, 3, '', '', '1932-07-30T12:00:00+00:00'),
    // array(4, 4, '', '', '1932-08-12T12:00:00+00:00'),
    // // array(4, 5, '', '', '1932-02-13T12:00:00+00:00'), // missing
    // array(4, 6, '', '', '1932-09-17T12:00:00+00:00'),
    // array(4, 7, '', '', '1932-10-11T12:00:00+00:00'),
    // array(4, 8, '', '', '1932-10-28T12:00:00+00:00'),
    // array(4, 9, '', '', '1932-11-12T12:00:00+00:00'),
    // array(4, 10, '', '', '1932-11-29T12:00:00+00:00'),
    // array(4, 11, '', '', '1932-12-22T12:00:00+00:00'),
    // array(4, 12, '', '', '1933-02-25T12:00:00+00:00'), // unknown issue number
    // array(5, 1, '', '', '1933-02-25T12:00:00+00:00'), // missing
    // array(5, 2, '', '', '1933-08-02T12:00:00+00:00'),
    // array(5, 3, '', '', '1933-08-26T12:00:00+00:00'),
    // // array(5, 4, '', '', '1933-02-25T12:00:00+00:00'), // missing
    // array(5, 5, '', '', '1933-09-30T12:00:00+00:00'),
    // array(5, 6, '', '', '1933-10-17T12:00:00+00:00'),
    // array(5, 7, '', '', '1933-10-28T12:00:00+00:00'),
    // array(5, 8, '', '', '1933-11-17T12:00:00+00:00'),
    // array(5, 9, '', '', '1933-11-29T12:00:00+00:00'),
    // array(5, 10, '', '', '1933-12-18T12:00:00+00:00'),
    // array(5, 11, '', '', '1934-01-23T12:00:00+00:00'),
    // array(5, 12, '', '', '1934-02-12T12:00:00+00:00'),
    // array(5, 13, '', '', '1934-02-28T12:00:00+00:00'),
    // array(5, 14, '', '', '1934-03-22T12:00:00+00:00'),
    // array(6, 1, '', '', '1934-06-30T12:00:00+00:00'),
    // array(6, 2, '', '', '1934-07-23T12:00:00+00:00'),
    // array(6, 3, '', '', '1934-08-16T12:00:00+00:00'),
    // array(6, 4, '', '', '1934-08-31T12:00:00+00:00'),
    // array(6, 5, '', '', '1934-09-15T12:00:00+00:00'),
    // array(6, 6, '', '', '1934-09-29T12:00:00+00:00'),
    // array(6, 7, '', '', '1934-10-15T12:00:00+00:00'),
    // array(6, 8, '', '', '1934-11-14T12:00:00+00:00'),
    // array(6, 9, '', '', '1934-11-30T12:00:00+00:00'),
    // array(6, 10, '', '', '1934-12-15T12:00:00+00:00'),
    // array(6, 11, '', '', '1935-01-19T12:00:00+00:00'),
    // array(6, 12, '', '', '1935-02-18T12:00:00+00:00'),
    // array(6, 13, '', '', '1935-03-09T12:00:00+00:00'),
    // array(7, 1, '', '', '1935-06-28T12:00:00+00:00'),
    // array(7, 2, '', '', '1935-07-15T12:00:00+00:00'),
    // array(7, 3, '', '', '1935-07-30T12:00:00+00:00'),
    // array(7, 4, '', '', '1935-08-16T12:00:00+00:00'),
    // array(7, 5, '', '', '1935-08-31T12:00:00+00:00'),
    // array(7, 6, '', '', '1935-09-14T12:00:00+00:00'),
    // array(7, 7, '', '', '1935-09-30T12:00:00+00:00'),
    // array(7, 8, '', '', '1935-10-21T12:00:00+00:00'),
    // array(7, 9, '', '', '1935-11-16T12:00:00+00:00'),
    // array(7, 10, '', '', '1935-11-29T12:00:00+00:00'),
    // array(7, 11, '', '', '1935-12-20T12:00:00+00:00'),
    // array(7, 12, '', '', '1936-01-21T12:00:00+00:00'),
    // array(7, 13, '', '', '1936-02-25T12:00:00+00:00'),
    // array(7, 14, '', '', '1936-03-18T12:00:00+00:00'),
    // array(8, 1, '', '', '1936-06-30T12:00:00+00:00'),
    // array(8, 2, '', '', '1936-07-15T12:00:00+00:00'),
    // array(8, 3, '', '', '1936-07-30T12:00:00+00:00'),
    // array(8, 4, '', '', '1936-08-15T12:00:00+00:00'),
    // array(8, 5, '', '', '1936-08-31T12:00:00+00:00'),
    // array(8, 6, '', '', '1936-09-15T12:00:00+00:00'),
    // array(8, 7, '', '', '1936-09-30T12:00:00+00:00'),
    // array(8, 8, '', '', '1936-10-17T12:00:00+00:00'),
    // array(8, 9, '', '', '1936-11-16T12:00:00+00:00'),
    // array(8, 10, '', '', '1936-11-30T12:00:00+00:00'),
    // // array(8, 11, '', '', '1936-??-??T12:00:00+00:00'), // missing
    // array(8, 12, '', '', '1937-01-30T12:00:00+00:00'),
    // array(8, NULL, 'xxxiii-international-eucharistic-congress', 'A Memorial of the XXXIII International Eucharistic Congress', '1937-02-15T12:00:00+00:00'), // unknown date
    // array(8, 13, '', '', '1937-02-27T12:00:00+00:00'),
    // array(8, 14, '', '', '1937-03-15T12:00:00+00:00'),
    // array(9, 1, '', '', '1937-06-30T12:00:00+00:00'),
    // array(9, 2, '', '', '1937-07-15T12:00:00+00:00'),
    // array(9, 3, '', '', '1937-07-30T12:00:00+00:00'),
    // array(9, 4, '', '', '1937-08-16T12:00:00+00:00'),
    // array(9, 5, '', '', '1937-08-31T12:00:00+00:00'),
    // array(9, 6, '', '', '1937-09-15T12:00:00+00:00'),
    // array(9, 7, '', '', '1937-09-30T12:00:00+00:00'),
    // array(9, 8, '', '', '1937-10-30T12:00:00+00:00'),
    // array(9, 9, '', '', '1937-11-16T12:00:00+00:00'),
    // array(9, NULL, 'december-1937', 'December 1937', '1937-12-01T11:00:00+00:00'),
    // array(9, 10, '', '', '1937-12-01T12:00:00+00:00'),
    // // array(9, 11, '', '', '1937-??-??T12:00:00+00:00'), // missing
    // // array(9, 12, '', '', '1937-??-??T12:00:00+00:00'), // missing
    // // array(9, 13, '', '', '1937-??-??T12:00:00+00:00'), // missing
    // array(9, 14, '', '', '1938-02-15T12:00:00+00:00'),
    // array(9, 15, '', '', '1938-03-15T12:00:00+00:00'),
    // array(10, 1, '', '', '1938-06-30T12:00:00+00:00'),
    // array(10, 2, '', '', '1938-07-15T12:00:00+00:00'),
    // array(10, 3, '', '', '1938-07-30T12:00:00+00:00'),
    // array(10, 4, '', '', '1938-08-17T12:00:00+00:00'),
    // array(10, 5, '', '', '1938-08-31T12:00:00+00:00'),
    // array(10, 6, '', '', '1938-09-16T12:00:00+00:00'),
    // array(10, 7, '', '', '1938-10-06T12:00:00+00:00'),
    // array(10, 8, '', '', '1938-11-18T12:00:00+00:00'),
    // array(10, NULL, 'christmas-1938', 'Christmas 1938', '1938-12-25T12:00:00+00:00'),
    // array(10, 9, '', '', '1939-12-31T12:00:00+00:00'),
    // array(10, 10, '', '', '1939-02-15T12:00:00+00:00'),
    // array(10, 11, '', '', '1939-03-07T12:00:00+00:00'),
    // array(11, 1, '', '', '1939-06-30T12:00:00+00:00'),
    // array(11, 2, '', '', '1939-07-15T12:00:00+00:00'),
    // array(11, 3, '', '', '1939-07-29T12:00:00+00:00'),
    // array(11, 4, '', '', '1939-08-31T12:00:00+00:00'),
    // // array(11, 5, '', '', '1939-??-??T12:00:00+00:00'), // missing
    // array(11, 6, '', '', '1939-09-19T12:00:00+00:00'),
    // array(11, 7, '', '', '1939-09-30T12:00:00+00:00'),
    // array(11, 8, '', '', '1939-11-06T12:00:00+00:00'),
    // array(11, 9, '', '', '1939-11-29T12:00:00+00:00'),
    // array(11, 10, 'christmas-issue-1939', 'Christmas Issue 1939', '1939-12-25T12:00:00+00:00'), // assumed #10, unknown date
    // // array(11, 11, '', '', '1940-??-??T12:00:00+00:00'), // missing
    // array(11, 12, '', '', '1940-02-28T12:00:00+00:00'),
    // array(12, 1, '', '', '1940-06-28T12:00:00+00:00'),
    // array(12, 2, '', '', '1940-07-17T12:00:00+00:00'),
    // array(12, 3, '', '', '1940-07-30T12:00:00+00:00'),
    // array(12, 4, '', '', '1940-08-24T12:00:00+00:00'),
    // array(12, 5, '', '', '1940-09-09T12:00:00+00:00'),
    // array(12, 6, '', '', '1940-09-24T12:00:00+00:00'),
    // array(12, 7, '', '', '1940-10-10T12:00:00+00:00'),
    // array(12, 8, '', '', '1940-11-15T12:00:00+00:00'),
    // array(12, 9, '', '', '1940-11-27T12:00:00+00:00'),
    // array(12, NULL, 'alumni-homecoming-souvenir-1940', 'Alumni Homecoming Souvenir 1940', '1940-12-01T12:00:00+00:00'),
    // array(12, 10, '', '', '1940-12-25T12:00:00+00:00'), // assumed 10
    // array(12, 11, '', '', '1941-01-31T12:00:00+00:00'),
    // array(12, 12, '', '', '1941-02-15T12:00:00+00:00'),
    // array(12, 13, '', '', '1941-03-12T12:00:00+00:00'),
    // array(13, 1, '', '', '1941-06-28T12:00:00+00:00'),
    // array(13, 2, '', '', '1941-07-12T12:00:00+00:00'),
    // array(13, 3, '', '', '1941-07-26T12:00:00+00:00'),
    // array(13, 4, '', '', '1941-08-09T12:00:00+00:00'),
    // array(13, 5, '', '', '1941-08-30T12:00:00+00:00'),
    // array(13, 6, '', '', '1941-09-13T12:00:00+00:00'),
    // array(13, 7, '', '', '1941-09-27T12:00:00+00:00'),
    // array(13, NULL, 'blue-eagle-supplement-1941', 'The GUIDON Blue Eagle Supplement 1941', '1941-09-27T12:00:00+00:00'), // unknown
    // array(13, 8, '', '', '1941-10-11T12:00:00+00:00'),
    // array(13, 9, '', '', '1941-11-08T12:00:00+00:00'),
    // array(13, 10, '', '', '1941-11-22T12:00:00+00:00'),
    // array(13, 1, 'vol-13-1946-no-1', 'The GUIDON Vol. 13 (1946), No. 1', '1946-10-01T12:00:00+00:00'), // repeated volume num
    // array(13, 2, 'vol-13-1946-no-2', 'The GUIDON Vol. 13 (1946), No. 2', '1946-11-01T12:00:00+00:00'), // repeated volume num
    // array(13, 3, 'vol-13-1946-no-3', 'The GUIDON Vol. 13 (1946), No. 3', '1946-12-20T12:00:00+00:00'), // repeated volume num
    // array(13, 4, 'vol-13-1946-no-4', 'The GUIDON Vol. 13 (1946), No. 4', '1947-02-07T12:00:00+00:00'), // repeated volume num
    // array(13, 5, 'vol-13-1946-no-5', 'The GUIDON Vol. 13 (1946), No. 5', '1947-03-13T12:00:00+00:00'), // repeated volume num
    // array(14, 1, '', '', '1947-08-14T12:00:00+00:00'),
    // array(14, 2, '', '', '1947-09-02T12:00:00+00:00'),
    // array(14, 3, '', '', '1947-09-19T12:00:00+00:00'),
    // array(14, 4, '', '', '1947-10-10T12:00:00+00:00'),
    // array(14, 5, '', '', '1947-10-30T12:00:00+00:00'),
    // array(14, 6, '', '', '1947-11-21T12:00:00+00:00'),
    // array(14, 7, '', '', '1947-12-08T12:00:00+00:00'),
    // array(14, 8, 'christmas-issue-1947', 'Christmas Issue 1947', '1947-12-19T12:00:00+00:00'),
    // array(14, 9, '', '', '1948-01-19T12:00:00+00:00'),
    // array(14, 10, '', '', '1948-02-16T12:00:00+00:00'),
    // array(14, 11, '', '', '1948-03-12T12:00:00+00:00'),
    // array(15, 1, 'august-1948', 'August 1948', '1948-08-01T12:00:00+00:00'),
    // array(15, 2, 'october-1948', 'October 1948', '1948-10-01T12:00:00+00:00'),
    // array(15, 3, 'december-1948', 'December 1948', '1948-12-01T12:00:00+00:00'),
  );

  for ($i = 0; $i < count($issues); $i++) {
    $new_post = array(
      'post_author' => 2,
      'post_date' => $issues[$i][4],
      'post_title' => empty($issues[$i][3]) ? ('The GUIDON Vol. ' . $issues[$i][0] . ', No. ' . $issues[$i][1]) : $issues[$i][3],
      'post_status' => 'publish',
      'post_type' => 'post',
      'post_category' => array(5),
      'meta_input' => array(
        'fixed_slug' => empty($issues[$i][2]) ? ('vol-' . $issues[$i][0] . '-no-' . $issues[$i][1]) : $issues[$i][2],
        'is_legacy' => 'true',
        'volume_num' => is_null($issues[$i][0]) ? '' : $issues[$i][0],
        'issue_num' => is_null($issues[$i][1]) ? '' : $issues[$i][1],
      ),
    );

    wp_insert_post($new_post);
  }

  return rest_ensure_response(array('message' => 'SUCCESS'));
}

?>