const express = require("express");
const sql = require("mssql");

const app = express();
app.use(express.json()); // Middleware untuk parsing JSON

// SQL Server configuration
var config = {
    "user": "usersmart", // Database username
    "password": "2016smart", // Database password
    "server": "172.16.1.111", // Server IP address
    "database": "smartweb_b/up", // Database name
    "options": {
        "encrypt": false // Disable encryption
    }
}

// Connect to SQL Server
sql.connect(config, err => {
    if (err) {
        throw err;
    }
    console.log("Connection Successful!");
});

// Define route for fetching data from SQL Server
app.get("/", (request, response) => {
    // Execute a SELECT query
    new sql.Request().query("SELECT * from vwAbsensiIT", (err, result) => {
        if (err) {
            console.error("Error executing query:", err);
        } else {
            response.send(result.recordset); // Send query result as response
            console.dir(result.recordset);
        }
    });
});

// Get data berdasarkan fsCardNo yang dibutuhkan
app.get("/api/absence", (request, response) => {
    const query = `
        SELECT * FROM vwAbsensiIT
        WHERE fsCardNo IN ('z121130','18698','z110030','Z110780','Z111086','z119163', 
            'Z54497', 'Z67254', 'Z80469', 'Z109553', 'z124187', 'Z119238', 'Z100665','z124187',
            '18992', '19525', '19721', 'Z125602', 'Z126397', 'Z126457','Z126577','19820','19820')
        ORDER BY fsName
    `;

    new sql.Request().query(query, (err, result) => {
        if (err) {
            console.error("Error executing query:", err);
            response.status(500).send("Error executing query");
        } else {
            response.json(result.recordset); // Send query result as JSON response
        }
    });
});

// Update status1 for a specific fsCardNo
app.put("/api/absence/:fsCardNo/status", (request, response) => {
    const { fsCardNo } = request.params;
    const { status1 } = request.body;

    const query = `
        UPDATE vwAbsensiIT
        SET status1 = @status1
        WHERE fsCardNo = @fsCardNo
    `;

    const requestSql = new sql.Request();
    requestSql.input('fsCardNo', sql.VarChar, fsCardNo);
    requestSql.input('status1', sql.VarChar, status1);

    requestSql.query(query, (err, result) => {
        if (err) {
            console.error("Error executing query:", err);
            response.status(500).send("Error executing query");
        } else {
            response.send("Status updated successfully");
        }
    });
});

// Start the server on port 3000
app.listen(3000, () => {
    console.log("Listening on port 3000...");
});