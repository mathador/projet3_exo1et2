import React from 'react';

import Notes from '../components/Notes';
import TagForm from '../components/TagForm';
import TagList from '../components/TagList';

const Dashboard = () => {
  return (
    <div className="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
      <h1 className="text-2xl font-bold mb-4">Tableau de bord</h1>

      {/* Notes Component */}
      <div className="mt-6 p-4 border border-neutral-200 dark:border-neutral-700 rounded-xl bg-white dark:bg-neutral-900">
        <Notes />
      </div>

      {/* TagForm Component */}
      <div className="mt-6 p-4 border border-neutral-200 dark:border-neutral-700 rounded-xl bg-white dark:bg-neutral-900">
        <TagForm />
      </div>

      {/* TagList Component */}
      <div className="mt-6 p-4 border border-neutral-200 dark:border-neutral-700 rounded-xl bg-white dark:bg-neutral-900">
        <TagList />
      </div>
    </div>
  );
};

export default Dashboard;
