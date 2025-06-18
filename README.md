# Smart Energy Dashboard

A modern energy monitoring dashboard built with Node.js and Chart.js.

## Features

- Real-time energy consumption monitoring
- User management system
- Interactive charts and visualizations
- Responsive design
- Secure authentication

## Prerequisites

- Node.js (v14 or higher)
- MongoDB
- npm or yarn

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd energy-dashboard
```

2. Install dependencies:
```bash
npm install
```

3. Create a .env file in the root directory with the following variables:
```
PORT=3000
MONGODB_URI=mongodb://localhost:27017/energy-dashboard
SESSION_SECRET=your-secret-key-here
JWT_SECRET=your-jwt-secret-here
NODE_ENV=development
```

4. Start the development server:
```bash
npm run dev
```

## Project Structure

```
/energy-dashboard/
│
├── backend/
│   ├── controllers/
│   ├── routes/
│   ├── models/
│   ├── services/
│   ├── database/
│   └── index.js
│
├── frontend/
│   ├── pages/
│   ├── components/
│   ├── styles/
│   └── main.js
│
├── public/
│
├── .env
├── README.md
├── package.json
└── .gitignore
```

## Development

- Frontend development: The frontend uses Chart.js for data visualization and Tailwind CSS (via CDN) for styling
- Backend development: The backend is built with Express.js and MongoDB
- API endpoints are documented in the respective route files

## License

MIT 