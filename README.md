# 🚀 GearX

**Tech gear store built with simplicity and purpose.**

**GearX** is a modern e-commerce platform designed to provide a seamless shopping experience for tech enthusiasts. Focused on performance and user-friendliness, it leverages powerful web technologies to deliver a responsive and efficient storefront.

## ✨ Features

- **🖱️ User-Friendly Interface** — Intuitive layout for easy navigation and product discovery.  
- **🔒 Secure Authentication** — Robust authentication system to protect user data.  
- **🛍️ Product Management** — Efficient tools for managing products, categories, and inventory.  
- **📱 Responsive Design** — Optimized for desktops, tablets, and mobile devices.  
- **⚙️ Modern Tech Stack** — Built with Symfony and styled using Tailwind CSS.

## 🧱 Tech Stack

| Layer        | Technology             |
|--------------|------------------------|
| **Backend**  | Symfony                |
| **Frontend** | Twig + Tailwind CSS    |
| **Database** | MySQL                  |
| **DevOps**   | Docker                 |
| **VCS**      | Git                    |

## ⚙️ Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Med-nadhir-djebbi/GearX.git
cd GearX
```

### 2. Set Up Environment Variables

- Duplicate `.env.dev` and rename it to `.env`
- Configure the required environment variables inside `.env`

### 3. Build and Run with Docker

```bash
docker-compose up --build
```

### 4. Install Dependencies

```bash
docker-compose exec app composer install
```

### 5. Run Database Migrations

```bash
docker-compose exec app php bin/console doctrine:migrations:migrate
```

### 6. Access the Application

Open your browser and go to: [http://localhost:8000](http://localhost:8000)

## 🖼️ UI Screenshots

### 🏠 Homepage

![image](https://github.com/user-attachments/assets/21e1f393-c882-4c7b-bc5d-8cc222b42593)

### 📄 Product Details Page

![image](https://github.com/user-attachments/assets/9f13744b-db9c-42ff-8b39-d58f9e41aef9)

## 🤝 Contributing

Contributions are welcome! Here's how to contribute:

1. **Fork** the repository
2. Create a new branch

```bash
git checkout -b feature/YourFeature
```

3. Commit your changes

```bash
git commit -m "Add YourFeature"
```

4. Push to your branch

```bash
git push origin feature/YourFeature
```

5. Open a **Pull Request**

## 📄 License

This project is licensed under the **MIT License**.  
See the [LICENSE](LICENSE) file for more details.
