import { useState, type ReactNode } from "react";

export default function Block({ num }: { num: number }) {
  interface User {
    id: number;
    name: string;
  }

  class UserManager {
    currentUser: User;

    constructor(user: User) {
      this.currentUser = user;
    }
    move(newUser: User) {
      this.currentUser = newUser;
      console.log(`Switched to ${newUser.name}`);
    }
  }

  const user1: User = { id: 1, name: "BOOm" };
  const user2: User = { id: 1, name: "Baam" };

  const switchUser = new UserManager(user1);

  const toggleSwitchUser = (user1: User, user2: User) => {
    
  };

  const [currentUser, setCurrentUser] = useState<User>();

  const clickedBlock = function (
    val: number,
    e: React.MouseEvent<HTMLDivElement>
  ) {
    console.log(switchUser.move(user2));
  };
  console.log(num);

  const renderBlock = Array.from({ length: num }).map((_, i) => (
    <div
      key={i}
      className="border-[1px] border-gray-500 w-[calc(3*4rem)] sm:w-[12rem] md:w-[15rem] h-[12rem] sm:h-[15rem] grid grid-cols-3"
    >
      {Array.from({ length: 9 }).map(
        (_, j): ReactNode => (
          <div
            key={j}
            onClick={(e) => clickedBlock(j, e)}
            className="border-[1px] border-gray-500 h-[4rem] sm:h-[5rem] w-[4rem] sm:w-[5rem] flex items-center justify-center"
          >
            {j + 1}
          </div>
        )
      )}
    </div>
  ));

  return <>{renderBlock}</>;
}
