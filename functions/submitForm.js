const { google } = require('googleapis');

exports.handler = async (event, context) => {
    if (event.httpMethod !== 'POST') {
        return { statusCode: 405, body: 'Method Not Allowed' };
    }

    const data = JSON.parse(event.body);
    const { name, email, message } = data;

    try {
        // Authenticate with Google Sheets API
        const auth = new google.auth.GoogleAuth({
            credentials: JSON.parse(process.env.GOOGLE_SERVICE_ACCOUNT_CREDENTIALS),
            scopes: ['https://www.googleapis.com/auth/spreadsheets'],
        });

        const sheets = google.sheets({ version: 'v4', auth });
        const spreadsheetId = 'YOUR_SPREADSHEET_ID';
        const range = 'Sheet1!A:C';

        await sheets.spreadsheets.values.append({
            spreadsheetId,
            range,
            valueInputOption: 'RAW',
            requestBody: {
                values: [[name, email, message, new Date().toISOString()]],
            },
        });

        return {
            statusCode: 200,
            body: JSON.stringify({ message: 'Form submitted successfully!' }),
        };
    } catch (error) {
        console.error(error);
        return {
            statusCode: 500,
            body: JSON.stringify({ message: 'Failed to submit form' }),
        };
    }
};