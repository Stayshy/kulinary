CREATE TABLE "users" (
  "id" serial PRIMARY KEY,
  "username" varchar(50),
  "email" varchar(100),
  "password" varchar(100),
  "role" enum(user,chef)
);

CREATE TABLE "recipes" (
  "id" serial PRIMARY KEY,
  "title" varchar(100),
  "description" text,
  "ingredients" text,
  "instructions" text,
  "chef_id" integer
);

CREATE TABLE "workshops" (
  "id" serial PRIMARY KEY,
  "title" varchar(100),
  "description" text,
  "date" timestamp,
  "chef_id" integer,
  "recipe_id" integer
);

CREATE TABLE "registrations" (
  "id" serial PRIMARY KEY,
  "user_id" integer,
  "workshop_id" integer,
  "registered_at" timestamp
);

ALTER TABLE "recipes" ADD FOREIGN KEY ("chef_id") REFERENCES "users" ("id");

ALTER TABLE "workshops" ADD FOREIGN KEY ("chef_id") REFERENCES "users" ("id");

ALTER TABLE "workshops" ADD FOREIGN KEY ("recipe_id") REFERENCES "recipes" ("id");

ALTER TABLE "registrations" ADD FOREIGN KEY ("user_id") REFERENCES "users" ("id");

ALTER TABLE "registrations" ADD FOREIGN KEY ("workshop_id") REFERENCES "workshops" ("id");
