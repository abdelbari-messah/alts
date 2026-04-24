<?php
$storageFile = __DIR__ . '/data/contact-submissions.csv';
$submissions = array();

if (is_file($storageFile)) {
    $handle = fopen($storageFile, 'rb');
    if ($handle !== false) {
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 6) {
                $submissions[] = array(
                    'submitted_at' => $row[0],
                    'ip' => $row[1],
                    'name' => $row[2],
                    'email' => $row[3],
                    'subject' => $row[4],
                    'message' => $row[5],
                );
            }
        }
        fclose($handle);
    }
}

$submissions = array_reverse($submissions);
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact submissions</title>
    <style>
      :root {
        color-scheme: light;
        --bg: #f4f1ea;
        --panel: #ffffff;
        --ink: #1f2937;
        --muted: #6b7280;
        --line: #d7d2c8;
        --accent: #0f766e;
      }

      * {
        box-sizing: border-box;
      }

      body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background: linear-gradient(180deg, #fbfaf7 0%, var(--bg) 100%);
        color: var(--ink);
      }

      main {
        max-width: 1100px;
        margin: 0 auto;
        padding: 40px 20px 64px;
      }

      header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 16px;
        align-items: end;
        margin-bottom: 24px;
      }

      h1 {
        margin: 0;
        font-size: 2rem;
      }

      .meta {
        color: var(--muted);
        margin: 6px 0 0;
      }

      .panel {
        background: var(--panel);
        border: 1px solid var(--line);
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(31, 41, 55, 0.08);
        overflow: hidden;
      }

      .empty {
        padding: 28px;
        color: var(--muted);
      }

      table {
        width: 100%;
        border-collapse: collapse;
      }

      th,
      td {
        padding: 14px 16px;
        text-align: left;
        vertical-align: top;
        border-bottom: 1px solid var(--line);
      }

      th {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        background: #faf8f3;
      }

      tr:last-child td {
        border-bottom: 0;
      }

      .subject {
        color: var(--accent);
        font-weight: 700;
      }

      .message {
        white-space: pre-wrap;
        max-width: 560px;
      }

      .actions {
        display: flex;
        gap: 12px;
        align-items: center;
      }

      .actions a {
        color: var(--accent);
        text-decoration: none;
        font-weight: 700;
      }

      @media (max-width: 900px) {
        table,
        thead,
        tbody,
        th,
        td,
        tr {
          display: block;
        }

        thead {
          position: absolute;
          left: -9999px;
        }

        tr {
          border-bottom: 1px solid var(--line);
        }

        td {
          border: 0;
          border-bottom: 1px solid #ece8df;
        }

        td::before {
          content: attr(data-label);
          display: block;
          font-size: 0.72rem;
          text-transform: uppercase;
          letter-spacing: 0.08em;
          color: var(--muted);
          margin-bottom: 6px;
        }
      }
    </style>
  </head>
  <body>
    <main>
      <header>
        <div>
          <h1>Contact submissions</h1>
          <p class="meta">Stored in data/contact-submissions.csv</p>
        </div>
        <div class="actions">
          <a href="contact.html">Back to contact page</a>
        </div>
      </header>

      <section class="panel">
        <?php if (empty($submissions)) : ?>
          <div class="empty">No submissions yet.</div>
        <?php else : ?>
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>IP</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($submissions as $submission) : ?>
                <tr>
                  <td data-label="Date"><?php echo htmlspecialchars($submission['submitted_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td data-label="Name"><?php echo htmlspecialchars($submission['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td data-label="Email"><?php echo htmlspecialchars($submission['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td data-label="Subject" class="subject"><?php echo htmlspecialchars($submission['subject'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td data-label="Message" class="message"><?php echo htmlspecialchars($submission['message'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td data-label="IP"><?php echo htmlspecialchars($submission['ip'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </section>
    </main>
  </body>
</html>